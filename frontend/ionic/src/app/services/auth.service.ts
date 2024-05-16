import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { SERVER_URL } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private apiUrl = SERVER_URL;

  constructor(private http: HttpClient) { }

  public isLoggedIn(): boolean {
    const token = localStorage.getItem('token');

    console.log("token", token);
    // todo: check token validity
    
    return !!token;
  }

  register(username: string, email: string, password: string): Observable<any> {
    const payload = { username, email, password };
    return this.http.post(`${this.apiUrl}/register`, payload);
  }

  login(username: string, password: string): Observable<any> {
    const payload = { username, password };
    return this.http.post(`${this.apiUrl}/login`, payload);
  }
}