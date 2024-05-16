using System;
using System.ComponentModel.DataAnnotations;


namespace Daysuntil.DTOs
{
    public class CountdownDto
    {
        [Required]
        [MaxLength(255)]
        public string Name { get; set; }

        [Required]
        public DateTime Datetime { get; set; }

        [Required]
        public bool IsPublic { get; set; }

        public int? CategoryId { get; set; }
    }
}