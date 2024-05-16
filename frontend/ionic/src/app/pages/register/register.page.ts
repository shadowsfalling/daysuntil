import { Component } from '@angular/core';
import { UserService } from '../../services/user.service';
import { ToastController } from '@ionic/angular';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.page.html',
  styleUrls: ['./register.page.scss'],
})
export class RegisterPage {
  username: string = "";
  email: string = "";
  password: string = "";

  constructor(private authService: AuthService, private toastController: ToastController) {}

  register() {
    this.authService.register(this.username, this.email, this.password).subscribe({
      next: (response) => {


        // todo: save token and redirect to home page

        console.log('Registration successful', response);
        this.presentToast('Registration successful.');
      },
      error: (error) => {
        console.error('Registration failed', error);
        this.presentToast('Registration failed. Please check your entries.');
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