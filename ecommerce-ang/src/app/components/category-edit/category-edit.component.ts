import { Category } from './../../models/category';
import { Component, OnInit, DoCheck } from '@angular/core';
import { HttpEventType, HttpResponse } from '@angular/common/http';
import { global } from '../../services/global';
import { UserService } from '../../services/user.services';
import { CategoryServices } from '../../services/category.services';
import { Router, ActivatedRoute, Params } from '@angular/router';


@Component({
  selector: 'app-category-edit',
  templateUrl: '../category-register/category-register.component.html',
  styleUrls: ['./category-edit.component.css'],
  providers: [UserService,CategoryServices ]
})
export class CategoryEditComponent implements OnInit {

  public identity : any;
  public categories: any;
  public category: Category;
  public token: string;
  public url: string;
  public is_edit: boolean;
  public is_subcategory: boolean;
  public numberimag: string;
  selectedFiles?: FileList;
  currentFile?: File;
  progress1 = 0;
  progress2 = 0;
  progress3 = 0;
  message = '';

  constructor(
    private _userService: UserService,
    private _router: Router,
    private _categoryService: CategoryServices,
  ){
    this.identity = this._userService.getIdentity();
    this.token = this._userService.getToken();
    this.numberimag ='';
    this.category = new Category(1,'','','');
    this.url = global.url;
    this.is_edit = true;
    this.is_subcategory = false;
  }

  ngOnInit(): void {

    if (this.identity.role != 'ROLE_ADMIN' || this.identity.role == 'null'){
      this._router.navigate(['error']);
    }else{
      this.loadCategories();
    }

  }

  loadCategories(){
    this._categoryService.getAllCategories().subscribe({
      next: (resp: any)=>{
        this.categories=resp.categories;
        this.is_subcategory= true;
      }
    });
  }

  selectcategory(event: any){
    this._categoryService.getCategorybyId(event.target.value).subscribe({
      next: (resp: any)=>{
        console.log(resp.category);
        this.category = resp.category;
      }
    })
  }

  selectFile(event: any): void {
    this.selectedFiles = event.target.files;
    this.numberimag =event.target.id;
    this.upload();
  }

  upload(): void {
    this.progress1 = 0;
    this.progress2 = 0;
    this.progress3 = 0;

    if (this.selectedFiles) {
      const file: File | null = this.selectedFiles.item(0);

      if (file) {
        this.currentFile = file;

        this._categoryService.upload(this.currentFile, this.token).subscribe({
          next: (event: any) => {
            if (event.type === HttpEventType.UploadProgress) {
              if (this.numberimag == 'image1'){
                this.progress1 = Math.round((100 * event.loaded) / event.total);
              }
              //this.progress1 = Math.round((100 * event.loaded) / event.total);
            } else if (event instanceof HttpResponse) {
              this.message = event.body.message;
              if (this.numberimag == 'image1'){
                this.category.image=event.body.image;

              }
              console.log(this.numberimag);
              console.log(this.category.image);
            }
          },
          error: (err: any) => {
            console.log(err);
            this.progress1 = 0;

            if (err.error && err.error.message) {
              this.message = err.error.message;
            } else {
              this.message = 'No se logro subir la imagen!';
            }

            this.currentFile = undefined;
          },
        });
      }

      this.selectedFiles = undefined;
    }
  }

  onSubmit(form: any){

  }

}
