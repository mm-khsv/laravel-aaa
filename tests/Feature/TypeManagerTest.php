<?php

namespace dnj\AAA\Tests\Feature;

use dnj\AAA\Models\Type;
use dnj\AAA\Models\TypeAbility;
use dnj\AAA\Models\TypeLocalizedDetails;
use dnj\AAA\Tests\TestCase;
use Illuminate\Auth\Access\AuthorizationException;

class TypeManagerTest extends TestCase
{
    public function testStore(): void
    {
        $child = Type::factory()->create();
        $type = $this->getTypeManager()->store(
            ['en' => ['title' => 'Admin']],
            ['blog_post_add', 'blog_post_edit'],
            [$child->getId()],
            ['k1' => 'v1'],
            true,
            true,
        );
        $this->assertDatabaseHas($type->getTable(), ['id' => $type->getId()]);
        $this->assertSame('Admin', $type->getLocalizedDetails('en')->getTitle());
        $this->assertSame(['k1' => 'v1'], $type->getMeta());
        $this->assertSame(['blog_post_add', 'blog_post_edit'], $type->getAbilities());
        $this->assertEqualsCanonicalizing([$type->getId(), $child->getId()], $type->getChildIds());
        $this->assertSame([$type->getId()], $type->getParentIds());
        $this->assertTrue($type->can('blog_post_add'));
        $this->assertFalse($type->cant('blog_post_add'));
        $this->assertTrue($type->canAny(['permission_1', 'blog_post_add']));
        $this->assertFalse($type->canAny(['permission_1', 'permission_2']));
        $this->assertTrue($type->canAll(['blog_post_edit', 'blog_post_add']));
        $this->assertFalse($type->canAll(['permission_1', 'blog_post_add']));

        $this->expectException(AuthorizationException::class);
        $type->authorize('permission_1');
    }

    public function testUpdate(): void
    {
        $type = Type::factory()
            ->has(TypeLocalizedDetails::factory()->withLang('en'), 'translates')
            ->has(TypeLocalizedDetails::factory()->withLang('ar'), 'translates')
            ->has(TypeAbility::factory(5), 'abilities')
            ->has(Type::factory(5), 'children')
            ->create();
        $newChild = Type::factory()->create();

        $changes = [
            'localizedDetails' => [
                'it' => [
                    'title' => 'test-it',
                ],
                'en' => [
                    'title' => 'test-en',
                ],
            ],
            'abilities' => array_merge(array_slice($type->getAbilities(), 2, 2), ['permission_1', 'permission_2']),
            'meta' => [1 => 2, 2 => 3],
            'childIds' => array_merge(array_slice($type->getChildIds(), 2, 2), [$newChild->getId()]),
        ];
        $type = $this->getTypeManager()->update(
            $type->getId(),
            $changes,
            true,
        );
        $this->assertSame('test-en', $type->getLocalizedDetails('en')->getTitle());
        $this->assertSame('test-it', $type->getLocalizedDetails('it')->getTitle());
        $this->assertNull($type->getLocalizedDetails('ar'));
        $this->assertEqualsCanonicalizing($changes['abilities'], $type->getAbilities());
        $this->assertSame($changes['meta'], $type->getMeta());
        $this->assertEqualsCanonicalizing($changes['childIds'], $type->getChildIds());
    }

    public function testDestroy(): void
    {
        $type = Type::factory()->create();
        $this->getTypeManager()->destroy($type, true);
        $this->assertModelMissing($type);
    }

    public function testGetGuestType(): void
    {
        $type = Type::factory()->create();
        config()->set('aaa.guestType', $type->getId());
        $this->assertSame($type->getId(), $this->getTypeManager()->getGuestTypeID());
        $this->assertSame($type->getId(), $this->getTypeManager()->getGuestType()->getId());

        config()->set('aaa.guestType', null);
        $this->assertNull($this->getTypeManager()->getGuestTypeID());
        $this->assertNull($this->getTypeManager()->getGuestType());
    }

    public function testFind(): void
    {
        $this->assertNull($this->getTypeManager()->find(-22));

        $type = Type::factory()->create();
        $this->assertSame($type->getId(), $this->getTypeManager()->find($type->getId())->getId());
    }
}
