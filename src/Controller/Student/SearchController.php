<?php
declare(strict_types=1);

namespace App\Controller\Student;

use Cake\Http\Response;

/**
 * Search Controller
 *
 * Handles search functionality for students
 */
class SearchController extends StudentAppController
{
    /**
     * Student search API endpoint
     *
     * Searches across courses and posts accessible to students
     *
     * @return \Cake\Http\Response
     */
    public function studentSearch(): Response
    {
        $this->request->allowMethod(['get']);

        $query = trim((string)$this->request->getQuery('q', ''));
        $limit = min((int)$this->request->getQuery('limit', 8), 20);
        $identity = $this->request->getAttribute('identity');

        $results = [
            'query' => $query,
            'results' => [],
            'total' => 0,
        ];

        if (strlen($query) < 2) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode($results));
        }

        $searchResults = [];

        // Search Published Posts
        $postsTable = $this->fetchTable('Posts');
        $posts = $postsTable->find()
            ->where([
                'Posts.published' => true,
                'OR' => [
                    'Posts.title LIKE' => "%{$query}%",
                    'Posts.body LIKE' => "%{$query}%",
                ],
            ])
            ->limit($limit)
            ->all();

        foreach ($posts as $post) {
            $searchResults[] = [
                'type' => 'post',
                'icon' => 'post',
                'title' => $post->title,
                'subtitle' => 'Post • ' . $post->created->format('M j, Y'),
                'url' => "/posts/view/{$post->slug}",
            ];
        }

        // Search Courses (student's enrolled courses)
        if ($identity) {
            $coursesTable = $this->fetchTable('Courses');
            $courses = $coursesTable->find()
                ->contain(['Subjects'])
                ->matching('CourseEnrollments', function ($q) use ($identity) {
                    return $q->where(['CourseEnrollments.user_id' => $identity->id]);
                })
                ->where([
                    'OR' => [
                        'Courses.name LIKE' => "%{$query}%",
                    ],
                ])
                ->limit($limit)
                ->all();

            foreach ($courses as $course) {
                $subtitle = 'Course';
                if ($course->subject) {
                    $subtitle .= ' • ' . $course->subject->name;
                }
                $searchResults[] = [
                    'type' => 'course',
                    'icon' => 'course',
                    'title' => $course->name,
                    'subtitle' => $subtitle,
                    'url' => "/student/courses/view/{$course->id}",
                ];
            }
        }

        // Sort by relevance (exact matches first)
        usort($searchResults, function ($a, $b) use ($query) {
            $aExact = stripos($a['title'], $query) === 0 ? 0 : 1;
            $bExact = stripos($b['title'], $query) === 0 ? 0 : 1;
            return $aExact - $bExact;
        });

        $results['results'] = array_slice($searchResults, 0, $limit);
        $results['total'] = count($searchResults);

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($results));
    }
}
