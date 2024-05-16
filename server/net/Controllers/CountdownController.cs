using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Authorization;
using Daysuntil.DTOs;

namespace Daysuntil.Controllers
{

    [Route("api/[controller]")]
    [ApiController]
    public class CountdownController : ControllerBase
    {
        private readonly ICountdownService _countdownService;

        public CountdownController(ICountdownService countdownService)
        {
            _countdownService = countdownService;
        }

        [HttpGet("{id}")]
        public async Task<IActionResult> Get(int id)
        {
            var userId = int.Parse(User.FindFirst(System.Security.Claims.ClaimTypes.NameIdentifier)?.Value);
            try
            {
                var countdown = await _countdownService.GetCountdownById(id, userId);
                return Ok(countdown);
            }
            catch (Exception e)
            {
                return StatusCode(403, new { error = e.Message });
            }
        }

        [HttpPost]
        [Authorize]
        public async Task<IActionResult> Create([FromBody] CountdownDto input)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            var userId = int.Parse(User.FindFirst(System.Security.Claims.ClaimTypes.NameIdentifier)?.Value);
            try
            {
                var newCountdownId = await _countdownService.CreateCountdown(input.Name,
                 input.Datetime,
                 input.IsPublic,
                 input.CategoryId.HasValue ? input.CategoryId!.Value : null,
                  userId);
                return CreatedAtAction(nameof(Get), new { id = newCountdownId }, new { message = "Countdown created successfully", countdown_id = newCountdownId });
            }
            catch (Exception e)
            {
                return StatusCode(403, new { error = e.Message });
            }
        }

        [HttpPut("{id}")]
        [Authorize]
        public async Task<IActionResult> Update(int id, [FromBody] CountdownDto input)
        {
            var userId = int.Parse(User.FindFirst(System.Security.Claims.ClaimTypes.NameIdentifier)?.Value);
            try
            {
                await _countdownService.UpdateCountdown(id, input.Name, input.Datetime, input.IsPublic, input.CategoryId.HasValue ? input.CategoryId!.Value : null, userId);
                return Ok(new { message = "Countdown updated successfully" });
            }
            catch (Exception e)
            {
                return StatusCode(403, new { error = e.Message });
            }
        }

        [HttpGet("upcoming")]
        public async Task<IActionResult> ShowAllUpcoming()
        {
            var countdowns = await _countdownService.GetAllUpcoming();
            return Ok(countdowns);
        }

        [HttpGet("expired")]
        public async Task<IActionResult> ShowAllExpired()
        {
            var countdowns = await _countdownService.GetAllExpired();
            return Ok(countdowns);
        }
    }
}
