import { Subcategory } from './../models/subcategory';
import { Injectable } from "@angular/core";
import {HttpClient, HttpHeaders, HttpRequest, HttpEvent } from '@angular/common/http';
import {Observable} from 'rxjs';
import { global } from "./global";
@Injectable()
export class SubcategoryService{
  public url: string;

  constructor(
    public _http: HttpClient
  ){
    this.url=global.url;
  }


  getbycategory(id: any): Observable<any>{
    let headers = new HttpHeaders().set('content-type','application/json');
    return this._http.get(this.url+'subcategorbycat/'+id,{headers:headers});
  }

}
