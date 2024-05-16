using System.ComponentModel.DataAnnotations;
using Microsoft.Extensions.Configuration.UserSecrets;

namespace Daysuntil.DTOs
{
    public class CategoryDTO
    {
        public int? Id { get; set; }
        [Required(ErrorMessage = " is required")]
        [StringLength(100, ErrorMessage = " must not exceed 100 characters.")]
        public required string Name { get; set; }
        [Required(ErrorMessage = " is required")]
        [StringLength(100, ErrorMessage = " must not exceed 100 characters.")]
        public required string Color { get; set; }
        public int? UserId { get; set; }
    }
}