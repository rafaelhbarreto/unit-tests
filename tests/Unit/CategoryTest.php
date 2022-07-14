<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @var CategoryRepository */
    protected $categoryRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = app(CategoryRepository::class);
    }

    /** @test */
    public function should_be_valid_when_create_a_category()
    {
        $categoryData = Category::factory()->raw();
        $category = $this->categoryRepository->create($categoryData);

        $this->assertInstanceOf(Category::class, $category);
    }

    /** @test */
    public function should_be_valid_when_find_a_category()
    {
        $category = Category::factory()->create();
        $this->assertNotNull($this->categoryRepository->find($category->id));
    }

    /** @test */
    public function should_be_valid_when_update_a_category()
    {
        $category = Category::factory()->create();

        $dataToUpdate = [
            'name' => $this->faker->name,
            'is_available' => $this->faker->randomElement([true, false])
        ];

        $this->categoryRepository->update($category->id, $dataToUpdate);

        $this->assertDatabaseHas('categories', $dataToUpdate);
    }

    /** @test */
    public function should_be_valid_when_get_all_categories()
    {
        $numberOfCategories = $this->faker->randomDigitNotZero();

        Category::factory()->count($numberOfCategories)->create();

        $this->assertCount($numberOfCategories, $this->categoryRepository->all());
    }

    /** @test */
    public function should_be_valid_when_delete_a_category()
    {
        Category::factory()->create();

        $this->categoryRepository->delete(1);

        $this->assertDatabaseCount('categories', 0);
    }
}
