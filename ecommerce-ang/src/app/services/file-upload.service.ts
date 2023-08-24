import { Injectable } from '@angular/core';
import { HttpClient, HttpRequest, HttpEvent } from '@angular/common/http';
import { Observable } from 'rxjs';
import { global } from './global';

@Injectable({
  providedIn: 'root'
})
export class FileUploadService {
  private url:string;

  constructor(private http: HttpClient) {
    this.url=global.url;
  }

  upload(file: File): Observable<HttpEvent<any>> {
    const formData: FormData = new FormData();

    formData.append('file0', file);
    console.log(formData);
    const req = new HttpRequest('POST', `${this.url}user/upload`, formData, {
      reportProgress: true,
      responseType: 'json'
    });

    return this.http.request(req);
  }

  /*getFiles(): Observable<any> {
    return this.http.get(`${this.url}files`);
  }*/
}
