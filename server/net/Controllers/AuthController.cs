using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Options;
using Daysuntil.Dtos;
using System.Security.Claims;


[ApiController]
[Route("[controller]")]
public class AuthController : ControllerBase
{
    private readonly IUserService _userService;
    private readonly ILogger<AuthController> _logger;

    public AuthController(IOptions<JwtSettings> jwtSettings, IUserService userService, ILogger<AuthController> logger)
    {
        _userService = userService;
        _logger = logger;
    }

    [HttpPost("register")]
    public async Task<IActionResult> Register([FromBody] RegisterDto model)
    {

        Console.WriteLine("model");
        Console.WriteLine(model);

        if (!ModelState.IsValid)
        {
            return BadRequest(ModelState);
        }

        var token = await _userService.RegisterAsync(model.Username, model.Email, model.Password);
        return Ok(new { Token = token });
    }

    [HttpPost("login")]
    public async Task<IActionResult> Login([FromBody] LoginDto model)
    {
        if (!ModelState.IsValid)
        {
            return BadRequest(ModelState);
        }

        var token = await _userService.LoginAsync(model.Username, model.Password);
        return Ok(new { Token = token });
    }

    [HttpGet("userinfo")]
    public async Task<IActionResult> GetUserInfo()
    {

        _logger.LogInformation("IsAuthenticated: {IsAuthenticated}", User.Identity.IsAuthenticated);

        if (!User.Identity.IsAuthenticated)
        {
            return Unauthorized("User is not authenticated.");
        }

        var userIdValue = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;

        if (string.IsNullOrEmpty(userIdValue) || !int.TryParse(userIdValue, out int userId))
        {
            return BadRequest("Invalid user ID.");
        }

        var user = await _userService.FindByIdAsync(userId);
        if (user == null)
        {
            return NotFound("User not found.");
        }

        return Ok(new { user.Username, user.Email });
    }
}