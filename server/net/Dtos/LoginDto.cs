using System.ComponentModel.DataAnnotations;

namespace Daysuntil.Dtos
{
    public class LoginDto
    {
        [Required(ErrorMessage = "Username is required")]
        [StringLength(100, ErrorMessage = "Username must not exceed 100 characters.")]
        public required string Username { get; set; }

        [Required(ErrorMessage = "Password is required")]
        public required string Password { get; set; }
    }
}