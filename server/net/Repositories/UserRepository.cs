using Daysuntil.Models;
using Microsoft.EntityFrameworkCore;

public interface IUserRepository
{
    Task<User> FindByUsernameAsync(string username);
    Task<bool> CheckPasswordAsync(User user, string password);
    Task<bool> CreateAsync(User user, string password);
    Task<User> FindByIdAsync(int id);
}

public class UserRepository : IUserRepository
{
    private readonly DaysUntilContext _context;

    public UserRepository(DaysUntilContext context)
    {
        _context = context;
    }

    public async Task<User> FindByUsernameAsync(string username)
    {
        return await _context.Users.FirstAsync(u => u.Username == username);
    }

    public async Task<bool> CheckPasswordAsync(User user, string password)
    {
        // todo: use password hashing
        return user.Password == password;
    }

    public async Task<bool> CreateAsync(User user, string password)
    {
        _context.Users.Add(user);
        
        // todo: use password hashing
        user.Password = password; 
        await _context.SaveChangesAsync();
        return true;
    }

    public async Task<User> FindByIdAsync(int id) {
        return await _context.Users.FirstAsync(u => u.Id == id);
    }
}