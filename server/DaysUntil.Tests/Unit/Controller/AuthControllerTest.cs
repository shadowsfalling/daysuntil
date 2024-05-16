using Moq;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Logging.Abstractions;
using Daysuntil.Controllers;
using Daysuntil.Dtos;

public class AuthControllerTest
{
    private readonly Mock<IUserService> _userServiceMock;
    private readonly AuthController _controller;

    public AuthControllerTest()
    {
        _userServiceMock = new Mock<IUserService>();
        _controller = new AuthController(_userServiceMock.Object, new NullLogger<AuthController>());
    }

    [Fact]
    public async Task TestRegisterSuccess()
    {
        var data = new RegisterDto { Username = "testuser", Password = "testpass", Email = "test@test.com" };
        var token = "example_token";
        _userServiceMock.Setup(x => x.RegisterAsync(data.Username, data.Email, data.Password))
            .ReturnsAsync(token);

        var result = await _controller.Register(data) as OkObjectResult;

        Assert.NotNull(result);
        Assert.Equal(200, result.StatusCode);
        Assert.Equal(token, result.Value);
    }

    [Fact]
    public async Task TestRegisterFailureMissingFields()
    {
        var data = new RegisterDto { Username = "testuser", Email = "", Password = "" };
        _controller.ModelState.AddModelError("Password", "Required");

        var result = await _controller.Register(data) as BadRequestObjectResult;

        Assert.NotNull(result);
        Assert.Equal(400, result.StatusCode);
    }

    [Fact]
    public async Task TestLoginSuccess()
    {
        var data = new LoginDto { Username = "testuser", Password = "validPassword" };
        var token = "example_token";
        _userServiceMock.Setup(x => x.LoginAsync(data.Username, data.Password))
            .ReturnsAsync(token);

        var result = await _controller.Login(data) as OkObjectResult;

        Assert.NotNull(result);
        Assert.Equal(200, result.StatusCode);

        Assert.Equal(token, result.Value);
    }

    [Fact]
    public async Task TestLoginFailure()
    {
        var data = new LoginDto { Username = "testuser", Password = "invalidPassword" };
        _userServiceMock.Setup(x => x.LoginAsync(data.Username, data.Password))
            .ReturnsAsync((string)null);

        var result = await _controller.Login(data);
        Assert.IsType<UnauthorizedResult>(result);
    }
}