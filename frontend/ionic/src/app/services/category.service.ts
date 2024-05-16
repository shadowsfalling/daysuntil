import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { SERVER_URL } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class CategoryService {

  private apiUrl = SERVER_URL;

  constructor(private http: HttpClient) { }

  public getCategories(): Observable<any> {
    return this.http.get(`${this.apiUrl}/categories`);
  }

}
