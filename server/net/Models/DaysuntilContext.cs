using Daysuntil.Models;
using Microsoft.EntityFrameworkCore;

public class DaysUntilContext : DbContext
{
    public DaysUntilContext(DbContextOptions<DaysUntilContext> options) : base(options)
    {
    }

    public DbSet<Category> Categories { get; set; }
    public DbSet<Countdown> Countdowns { get; set; }
    public DbSet<User> Users { get; set; }

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        base.OnModelCreating(modelBuilder);

        modelBuilder.Entity<User>(entity =>
        {
            entity.HasKey(u => u.Id);
            entity.Property(u => u.Username).IsRequired();
            entity.Property(u => u.Email).IsRequired();
            entity.HasIndex(u => u.Email).IsUnique();
            entity.Property(u => u.Password).IsRequired();

            entity.HasMany(u => u.Categories)
                  .WithOne(c => c.User)
                  .HasForeignKey(c => c.UserId)
                  .OnDelete(DeleteBehavior.Cascade);

            entity.HasMany(u => u.Countdowns)
                  .WithOne(cd => cd.User)
                  .HasForeignKey(cd => cd.UserId)
                  .OnDelete(DeleteBehavior.Cascade);
        });

        modelBuilder.Entity<Category>(entity =>
        {
            entity.HasKey(c => c.Id);
            entity.Property(c => c.Name).IsRequired();
            entity.Property(c => c.Color).IsRequired();
            entity.HasOne(c => c.User)
                  .WithMany(u => u.Categories)
                  .HasForeignKey(c => c.UserId);
        });

        modelBuilder.Entity<Countdown>(entity =>
        {
            entity.HasKey(cd => cd.Id);
            entity.Property(cd => cd.Title).IsRequired();
            entity.Property(cd => cd.DateTime).IsRequired();
            entity.HasOne(cd => cd.User)
                  .WithMany(u => u.Countdowns)
                  .HasForeignKey(cd => cd.UserId);
            entity.HasOne(cd => cd.Category)
                  .WithMany(c => c.Countdowns)
                  .HasForeignKey(cd => cd.CategoryId)
                  .OnDelete(DeleteBehavior.SetNull);
        });
    }
}