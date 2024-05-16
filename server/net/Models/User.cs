using System.Collections.Generic;

namespace Daysuntil.Models
{
    public class User
    {
        public int Id { get; set; }
        public required string Username { get; set; }
        public required string Email { get; set; }
        public required string Password { get; set; }

        public ICollection<Category>? Categories { get; set; }
        public ICollection<Countdown>? Countdowns { get; set; }

        public User() {

        }

        public User(string username, string email, string password) {
            Username = username;
            Email = email;
            Password = password;
        }
    }
}