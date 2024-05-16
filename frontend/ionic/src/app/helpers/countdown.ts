
export function startCountdown(datum_zeit: string, callback: (countdown: string) => void) {
    const countdown = setInterval(() => {
      const now = new Date().getTime();
      const eventDate = new Date(datum_zeit).getTime();
      const distance = eventDate - now;
  
      if (distance < 0) {
        clearInterval(countdown);
        callback("Termin abgelaufen");
        return;
      }
  
      const days = Math.floor(distance / (1000 * 60 * 60 * 24));
      const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((distance % (1000 * 60)) / 1000);
  
      callback(`${days} Tage ${hours} Std. ${minutes} Min. ${seconds} Sek.`);
    }, 1000);
  }