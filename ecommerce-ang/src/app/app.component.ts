import { Category } from './models/category';
import { Subcategory } from './models/subcategory';
import { Component, OnInit, DoCheck } from '@angular/core';
import { UserService } from './services/user.services';
import { global } from 'src/app/services/global';
import { CategoryServices } from './services/category.services';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  providers: [UserService, CategoryServices]
})
export class AppComponent implements OnInit{
  title = 'ecommerce-ang';
  public categories: any;
  public subcategory: Array<Subcategory>;
  public identity:any;
  public token:any;
  public url:any;

  constructor(
    private _userService: UserService,
    private _categoryService: CategoryServices
  ){
    this.url=global.url;
    this.categories = '';
    this.subcategory = new Array<Subcategory>;
  }

  ngOnInit(): void {
    this.loadCategories();
  }

  ngDoCheck(){
    this.loadUser();
  }

  loadUser(){
    this.identity= this._userService.getIdentity();
    this.token=this._userService.getToken();
  }

  loadCategories(){
    this._categoryService.getAllCategories().subscribe({
      next: (resp: any)=>{
        this.categories=resp.categories;
      }
    });
  }


}
