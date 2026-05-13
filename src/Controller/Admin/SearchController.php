<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Http\Response;

/**
 * Search Controller
 *
 * Handles global search functionality across the admin dashboard
 */
class SearchController extends AdminAppController
{
    /**
     * Global search API endpoint
     *
     * Searches across users, posts, classes, subjects, and courses
     *
     * @return \Cake\Http\Response
     */
    public function search(): Response
    {
        $this->request->allowMethod(['get']);

        $query = trim((string)$this->request->getQuery('q', ''));
        $limit = min((int)$this->request->getQuery('limit', 8), 20);

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

        // Search Users (Teachers, Students, Admins)
        $usersTable = $this->fetchTable('Users');
        $users = $usersTable->find()
            ->where([
                'OR' => [
                    'Users.name LIKE' => "%{$query}%",
                    'Users.email LIKE' => "%{$query}%",
                ],
            ])
            ->limit($limit)
            ->all();

        foreach ($users as $user) {
            $roleIcon = match ($user->role) {
                'teacher' => 'teacher',
                'student' => 'student',
                default => 'user',
            };
            $searchResults[] = [
                'type' => 'user',
                'icon' => $roleIcon,
                'title' => $user->name,
                'subtitle' => ucfirst($user->role) . ' • ' . $user->email,
                'url' => "/admin/users/view/{$user->id}",
            ];
        }

        // Search Posts
        $postsTable = $this->fetchTable('Posts');
        $posts = $postsTable->find()
            ->where([
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
                'subtitle' => ($post->published ? 'Published' : 'Draft') . ' • ' . $post->created->format('M j, Y'),
                'url' => "/posts/view/{$post->slug}",
            ];
        }

        // Search Classes
        $classesTable = $this->fetchTable('Classes');
        $classes = $classesTable->find()
            ->where([
                'OR' => [
                    'Classes.name LIKE' => "%{$query}%",
                    'Classes.section LIKE' => "%{$query}%",
                ],
            ])
            ->limit($limit)
            ->all();

        foreach ($classes as $class) {
            $searchResults[] = [
                'type' => 'class',
                'icon' => 'class',
                'title' => $class->name . ($class->section ? " ({$class->section})" : ''),
                'subtitle' => 'Class • ' . ($class->academic_year ?? 'No year'),
                'url' => "/admin/classes/view/{$class->id}",
            ];
        }

        // Search Subjects
        $subjectsTable = $this->fetchTable('Subjects');
        $subjects = $subjectsTable->find()
            ->where([
                'OR' => [
                    'Subjects.name LIKE' => "%{$query}%",
                    'Subjects.code LIKE' => "%{$query}%",
                ],
            ])
            ->limit($limit)
            ->all();

        foreach ($subjects as $subject) {
            $searchResults[] = [
                'type' => 'subject',
                'icon' => 'subject',
                'title' => $subject->name,
                'subtitle' => 'Subject' . ($subject->code ? " • {$subject->code}" : ''),
                'url' => "/admin/subjects/view/{$subject->id}",
            ];
        }

        // Search Courses
        $coursesTable = $this->fetchTable('Courses');
        $courses = $coursesTable->find()
            ->contain(['Subjects', 'Classes'])
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
                'url' => "/admin/courses/view/{$course->id}",
            ];
        }

        // Limit total results and sort by relevance (exact matches first)
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
