using Daysuntil.Models;

public interface IUserService
{
    Task<string> RegisterAsync(string username, string email, string password);
    Task<string> LoginAsync(string username, string password);
    Task<User> GetUserByUsername(string username);
    Task<User> FindByIdAsync(int id);
}

public class UserService : IUserService
{
    private readonly IUserRepository _userRepository;
    private readonly IJwtHandler _jwtHandler;

    public UserService(IUserRepository userRepository, IJwtHandler jwtHandler)
    {
        _userRepository = userRepository;
        _jwtHandler = jwtHandler;
    }

    public async Task<string> RegisterAsync(string username, string email, string password)
    {
        var user = new User {
             Email = email,
             Password = password,
              Username = username
        };

        var result = await _userRepository.CreateAsync(user, password);
        if (!result)
            throw new Exception("Could not create user");
        return _jwtHandler.GenerateToken(user);
    }

    public async Task<string> LoginAsync(string username, string password)
    {
        var user = await _userRepository.FindByUsernameAsync(username);
        if (user == null || !await _userRepository.CheckPasswordAsync(user, password))
            throw new Exception("Authentication failed");

        return _jwtHandler.GenerateToken(user);
    }

    public async Task<User> GetUserByUsername(string username)
    {
        return await _userRepository.FindByUsernameAsync(username);
    }

    public async Task<User> FindByIdAsync(int id) {
        return await _userRepository.FindByIdAsync(id);
    }
}