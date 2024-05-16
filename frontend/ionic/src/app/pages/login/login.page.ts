import { Component } from '@angular/core';
import { ToastController } from '@ionic/angular';
import { AuthService } from '../../services/auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
})
export class LoginPage {
  public username: string = "";
  public password: string = "";

  constructor(private authService: AuthService, private toastController: ToastController, private router: Router) {}

  login() {
    this.authService.login(this.username, this.password).subscribe({
      next: (response) => {

        localStorage.setItem('token', response.token);

        console.log('Login successful', response);
        this.presentToast('Login successful.');

        this.router.navigateByUrl('home');

      },
      error: (error) => {
        console.error('Login failed', error);
        this.presentToast('Login failed. Please try again.');
      }
    });
  }

  async presentToast(message: string) {
    const toast = await this.toastController.create({
      message,
      duration: 2000
    });
    toast.present();
  }
}