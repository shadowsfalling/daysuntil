import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { SERVER_URL } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class CountdownService {

  private apiUrl = SERVER_URL;

  constructor(private http: HttpClient) { }

  getCountdowns(): Observable<any> {
    return this.http.get(`${this.apiUrl}/countdowns/upcoming`);
  }

  getPastCountdowns(): Observable<any> {
    return this.http.get(`${this.apiUrl}/countdowns/past`);
  }

  getCountdownById(id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/countdown/${id}`);
  }
  
  createCountdown(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/countdown`, data);
  }
  
  updateCountdown(id: number, data: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/countdown/${id}`, data);
  }
}
