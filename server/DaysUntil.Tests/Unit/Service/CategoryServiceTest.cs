using Moq;
using Daysuntil.Models;
using Daysuntil.DTOs;

public class CategoryServiceTests
{
    private readonly Mock<ICategoryRepository> _mockRepository;
    private readonly CategoryService _categoryService;

    public CategoryServiceTests()
    {
        _mockRepository = new Mock<ICategoryRepository>();
        _categoryService = new CategoryService(_mockRepository.Object);
    }

    [Fact]
    public async Task CreateCategory_CreatesNewCategory()
    {
        // Arrange
        var categoryDto = new CategoryDTO { Name = "Test Category", Color = "#FFFFFF" };
        var userId = 1;
        var category = new Category { Name = categoryDto.Name, Color = categoryDto.Color, UserId = userId };

        _mockRepository.Setup(repo => repo.AddAsync(It.IsAny<Category>()))
                       .ReturnsAsync(category);

        // Act
        var result = await _categoryService.CreateAsync(categoryDto, userId);

        // Assert
        _mockRepository.Verify(repo => repo.AddAsync(It.IsAny<Category>()), Times.Once());
        Assert.NotNull(result);
        Assert.Equal(categoryDto.Name, result.Name);
        Assert.Equal(categoryDto.Color, result.Color);
    }

    [Fact]
    public async Task GetAllCategories_ReturnsAllCategories()
    {
        // Arrange
        var categories = new List<Category> {
            new Category { Id = 1, Name = "Category 1", Color = "#FFFFFF", UserId = 1 },
            new Category { Id = 2, Name = "Category 2", Color = "#000000", UserId = 1 }
        };
        _mockRepository.Setup(repo => repo.GetAllAsync()).ReturnsAsync(categories);

        // Act
        var result = await _categoryService.GetAllAsync();

        // Assert
        _mockRepository.Verify(repo => repo.GetAllAsync(), Times.Once());
        Assert.Equal(2, result.Count);
        Assert.Contains(result, c => c.Name == "Category 1" && c.Color == "#FFFFFF");
        Assert.Contains(result, c => c.Name == "Category 2" && c.Color == "#000000");
    }

    [Fact]
    public async Task DeleteCategory_DeletesCategory()
    {
        // Arrange
        int categoryId = 1;
        _mockRepository.Setup(repo => repo.DeleteAsync(categoryId)).Returns(Task.CompletedTask);

        // Act
        await _categoryService.DeleteAsync(categoryId);

        // Assert
        _mockRepository.Verify(repo => repo.DeleteAsync(categoryId), Times.Once());
    }
}