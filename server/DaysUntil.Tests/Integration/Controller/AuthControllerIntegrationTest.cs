using Flurl.Http.Testing;
using Flurl.Http;
using Daysuntil.DTOs;

public class AuthControllerIntegrationTest
{
    private HttpTest _httpTest;

    public AuthControllerIntegrationTest()
    {
        _httpTest = new HttpTest();
    }

    public void Dispose()
    {
        _httpTest.Dispose();
    }

    [Fact]
    public async Task TestRegister()
    {
  _httpTest.RespondWithJson(new { message = "User successfully registered", userId = 123, token = "example_token" }, 201);

    var response = await "http://localhost:8000/api/auth/register"
        .PostJsonAsync(new { username = "testuser", email = "test@example.com", password = "password" });

    Assert.Equal(201, response.StatusCode);
    var body = await response.GetJsonAsync<AuthResponseDto>();
    Assert.Equal("User successfully registered", body.Message);
    Assert.Equal(123, body.UserId);
    Assert.NotNull(body.Token);

    _httpTest.ShouldHaveCalled("http://localhost:8000/api/auth/register")
        .WithVerb(HttpMethod.Post)
        .WithContentType("application/json");
    }

    [Fact]
    public async Task TestLogin()
    {
 _httpTest.RespondWithJson(new { token = "example_token", message = "Login successful" }, 200);

    var response = await "http://localhost:8000/api/auth/login"
        .PostJsonAsync(new { username = "testuser", password = "password" });

    Assert.Equal(200, response.StatusCode);
    var body = await response.GetJsonAsync<LoginResponseDto>();
    Assert.Equal("Login successful", body.Message);
    Assert.Equal("example_token", body.Token);

    _httpTest.ShouldHaveCalled("http://localhost:8000/api/auth/login")
        .WithVerb(HttpMethod.Post)
        .WithContentType("application/json");
    }
}