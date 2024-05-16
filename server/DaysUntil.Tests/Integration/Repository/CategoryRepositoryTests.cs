using Microsoft.EntityFrameworkCore;
using Daysuntil.Models;

public class CategoryRepositoryTests : IDisposable
{
    private readonly DaysUntilContext _context;

    public CategoryRepositoryTests()
    {
        var options = new DbContextOptionsBuilder<DaysUntilContext>()
            .UseInMemoryDatabase(databaseName: "DaysUntilTestDb")
            .Options;
        _context = new DaysUntilContext(options);
        _context.Database.EnsureCreated();
    }

    public void Dispose()
    {
        _context.Database.EnsureDeleted();
        _context.Dispose();
    }

    [Fact]
    public async Task TestAddCategory()
    {
        var repository = new CategoryRepository(_context);
        var category = new Category { Name = "Test Category", Color = "#FFFFFF", UserId = 1 };

        var addedCategory = await repository.AddAsync(category);

        Assert.Equal(category.Name, addedCategory.Name);
        Assert.Equal(category.Color, addedCategory.Color);
        Assert.Equal(category.UserId, addedCategory.UserId);

        Assert.True(_context.Categories.Any(c => c.Id == addedCategory.Id));
    }

    [Fact]
    public async Task TestUpdateCategory()
    {
        var repository = new CategoryRepository(_context);
        var category = new Category { Name = "Existing Category", Color = "#000000", UserId = 1 };

        var addedCategory = await repository.AddAsync(category);
        addedCategory.Name = "Updated Category";
        addedCategory.Color = "#FFFFFF";

        await repository.UpdateAsync(addedCategory);

        var updatedCategory = await repository.GetByIdAsync(addedCategory.Id);

        Assert.Equal("Updated Category", updatedCategory.Name);
        Assert.Equal("#FFFFFF", updatedCategory.Color);
    }

    [Fact]
    public async Task TestDeleteCategory()
    {
        var repository = new CategoryRepository(_context);
        var category = new Category { Name = "Category to delete", Color = "#000000", UserId = 1 };

        var addedCategory = await repository.AddAsync(category);

        await repository.DeleteAsync(addedCategory.Id);

        Assert.False(_context.Categories.Any(c => c.Id == addedCategory.Id));
    }

    [Fact]
    public async Task TestFindAll()
    {
        var repository = new CategoryRepository(_context);
        await repository.AddAsync(new Category { Name = "Category 1", Color = "#000000", UserId = 1 });
        await repository.AddAsync(new Category { Name = "Category 2", Color = "#FFFFFF", UserId = 1 });
        await repository.AddAsync(new Category { Name = "Category 3", Color = "#123456", UserId = 2 });

        var categories = await repository.GetAllAsync();

        Assert.Equal(3, categories.Count);
        Assert.Contains(categories, c => c.Name == "Category 1" && c.UserId == 1);
    }
}