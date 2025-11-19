<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;

/**
 * Posts Model
 *
 * @method \App\Model\Entity\Post newEmptyEntity()
 * @method \App\Model\Entity\Post newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Post> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Post get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Post findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Post patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Post> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Post|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Post saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Post>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Post>|false find(mixed $type = 'all', array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Post>|\App\Model\Entity\Post[] paginate(\Cake\Datasource\Paging\PaginatedInterface|array $target, array $settings = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PostsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('posts');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('title')
            ->maxLength('title', 200)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('slug')
            ->maxLength('slug', 200)
            ->allowEmptyString('slug');

        $validator
            ->scalar('body')
            ->requirePresence('body', 'create')
            ->notEmptyString('body');

        $validator
            ->boolean('published')
            ->allowEmptyString('published');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['slug']), ['errorField' => 'slug']);

        return $rules;
    }

    /**
     * Finder to fetch published posts ordered by recency.
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query instance.
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findPublished(SelectQuery $query): SelectQuery
    {
        return $query
            ->where(['Posts.published' => true])
            ->orderDesc('Posts.created');
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        parent::beforeSave($event, $entity, $options);

        $slug = trim((string)$entity->slug);
        if ($slug === '') {
            $title = (string)$entity->title;
            $entity->slug = $this->generateUniqueSlug($title ?: Text::uuid(), $entity->id);
        }
    }

    protected function generateUniqueSlug(string $value, $excludeId = null): string
    {
        $base = Text::slug(mb_strtolower($value)) ?: 'post';
        $base = mb_substr($base, 0, 190);
        $slug = $base;
        $suffix = 1;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = mb_substr($base, 0, 190 - (strlen((string)$suffix) + 1)) . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    protected function slugExists(string $slug, $excludeId = null): bool
    {
        $conditions = ['slug' => $slug];
        if ($excludeId !== null) {
            $conditions['id !='] = $excludeId;
        }

        return $this->exists($conditions);
    }
}

