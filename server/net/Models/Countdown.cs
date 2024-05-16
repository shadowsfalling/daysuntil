using System;

namespace Daysuntil.Models
{
    public class Countdown
    {
        public int Id { get; set; }
        public required string Title { get; set; }
        public required DateTime DateTime { get; set; }
        public bool IsPublic { get; set; }
        public int? CategoryId { get; set; }
        public required int UserId { get; set; }

        public Category? Category { get; set; }
        public User? User { get; set; }
    }
}