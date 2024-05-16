namespace Daysuntil.Models
{
    public class Category
    {
        public int Id { get; set; }
        public required string Name { get; set; }
        public required string Color { get; set; }
        public required int UserId { get; set; }

        public User? User { get; set; }
        public ICollection<Countdown>? Countdowns { get; set; }

        public Category() {
            
        }
    }
}