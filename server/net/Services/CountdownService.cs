using Daysuntil.Models;

public interface ICountdownService
{
    Task<Countdown> GetCountdownById(int id, int userId);
    Task<int> CreateCountdown(string name, DateTime datetime, bool isPublic, int? categoryId, int userId);
    Task UpdateCountdown(int id, string title, DateTime datetime, bool isPublic, int? categoryId, int userId);
    Task<IEnumerable<Countdown>> GetAllUpcoming();
    Task<IEnumerable<Countdown>> GetAllExpired();
}

public class CountdownService : ICountdownService
{
    private readonly ICountdownRepository _countdownRepository;

    public CountdownService(ICountdownRepository countdownRepository)
    {
        _countdownRepository = countdownRepository;
    }

    public async Task<Countdown> GetCountdownById(int id, int userId)
    {
        var countdown = await _countdownRepository.GetByIdAsync(id);
        if (countdown.UserId != userId)
        {
            throw new UnauthorizedAccessException("You do not have access to this countdown.");
        }
        return countdown;
    }

    public async Task<int> CreateCountdown(string name, DateTime datetime, bool isPublic, int? categoryId, int userId)
    {
        var countdown = new Countdown
        {
            Title = name,
            DateTime = datetime,
            IsPublic = isPublic,
            CategoryId = categoryId,
            UserId = userId
        };
        await _countdownRepository.AddAsync(countdown);
        return countdown.Id;
    }

    public async Task UpdateCountdown(int id, string title, DateTime datetime, bool isPublic, int? categoryId, int userId)
    {
        var countdown = await _countdownRepository.GetByIdAsync(id);
        if (countdown.UserId != userId)
        {
            throw new UnauthorizedAccessException("You do not have access to update this countdown.");
        }
        countdown.Title = title;
        countdown.DateTime = datetime;
        countdown.IsPublic = isPublic;
        countdown.CategoryId = categoryId;
        await _countdownRepository.UpdateAsync(countdown);
    }

    public async Task DeleteCountdown(int id)
    {
        await _countdownRepository.DeleteAsync(id);
    }

    public async Task<IEnumerable<Countdown>> GetAllUpcoming()
    {
        return await _countdownRepository.GetAllUpcomingAsync();
    }

    public async Task<IEnumerable<Countdown>> GetAllExpired()
    {
        return await _countdownRepository.GetAllExpiredAsync();
    }
}