using Daysuntil.Models;
using Daysuntil.DTOs;

public interface ICategoryService
{
    Task<List<CategoryDTO>> GetAllAsync();
    Task<CategoryDTO> GetByIdAsync(int id);
    Task<Category> CreateAsync(CategoryDTO categoryDto, int userId);
    Task UpdateAsync(CategoryDTO categoryDto);
    Task DeleteAsync(int id);
}

public class CategoryService : ICategoryService
{
    private readonly ICategoryRepository _repository;

    public CategoryService(ICategoryRepository repository)
    {
        _repository = repository;
    }

    public async Task<List<CategoryDTO>> GetAllAsync()
    {
        var categories = await _repository.GetAllAsync();
        return categories.ConvertAll(c => new CategoryDTO { Id = c.Id, Name = c.Name, Color = c.Color, UserId = c.UserId });
    }

    public async Task<CategoryDTO> GetByIdAsync(int id)
    {
        var category = await _repository.GetByIdAsync(id);
        return new CategoryDTO { Id = category.Id, Name = category.Name, Color = category.Color, UserId = category.UserId };
    }

    public async Task<Category> CreateAsync(CategoryDTO categoryDto, int userId)
    {
        var category = new Category { Name = categoryDto.Name, Color = categoryDto.Color, UserId = userId };
        await _repository.AddAsync(category);
        return category;
    }

    public async Task UpdateAsync(CategoryDTO categoryDto)
    {
        var category = await _repository.GetByIdAsync(categoryDto.Id!.Value);
        if (category == null)
        {
            throw new ArgumentException("Category not found.");
        }
        category.Name = categoryDto.Name;
        category.Color = categoryDto.Color;

        await _repository.UpdateAsync(category);
    }

    public async Task DeleteAsync(int id)
    {
        await _repository.DeleteAsync(id);
    }
}