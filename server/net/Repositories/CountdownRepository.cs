using Daysuntil.Models;
using Microsoft.EntityFrameworkCore;

public interface ICountdownRepository
{
    Task<Countdown> GetByIdAsync(int id);
    Task<int> AddAsync(Countdown countdown);
    Task UpdateAsync(Countdown countdown);
    Task DeleteAsync(int id);
    Task<List<Countdown>> GetAllUpcomingAsync();
    Task<List<Countdown>> GetAllExpiredAsync();
}

public class CountdownRepository : ICountdownRepository
{
    private readonly DaysUntilContext _context;

    public CountdownRepository(DaysUntilContext context)
    {
        _context = context;
    }

    public async Task<Countdown> GetByIdAsync(int id)
    {
        return await _context.Set<Countdown>().FindAsync(id);
    }

    public async Task<int> AddAsync(Countdown countdown)
    {
        _context.Set<Countdown>().Add(countdown);
        await _context.SaveChangesAsync();
        return countdown.Id;
    }

    public async Task UpdateAsync(Countdown countdown)
    {
        _context.Entry(countdown).State = EntityState.Modified;
        await _context.SaveChangesAsync();
    }

    public async Task DeleteAsync(int id)
    {
        var countdown = await GetByIdAsync(id);
        if (countdown != null)
        {
            _context.Set<Countdown>().Remove(countdown);
            await _context.SaveChangesAsync();
        }
    }

    public async Task<List<Countdown>> GetAllUpcomingAsync()
    {
        return await _context.Set<Countdown>()
            .Where(c => c.DateTime > DateTime.Now)
            .ToListAsync();
    }

    public async Task<List<Countdown>> GetAllExpiredAsync()
    {
        return await _context.Set<Countdown>()
            .Where(c => c.DateTime <= DateTime.Now)
            .ToListAsync();
    }
}