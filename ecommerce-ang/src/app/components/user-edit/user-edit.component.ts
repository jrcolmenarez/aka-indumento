import { Component, OnInit } from '@angular/core';
import { User } from './../../models/user';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { global } from 'src/app/services/global';
import { UserService } from '../../services/user.services';
import { HttpClient, HttpHeaders,HttpEventType, HttpResponse } from '@angular/common/http';

@Component({
  selector: 'app-user-edit',
  templateUrl: '../register/register.component.html',
  styleUrls: ['./user-edit.component.css'],
  providers: [UserService]
})
export class UserEditComponent implements OnInit {

  public user: User;
  public is_edit: boolean;
  public status: string;
  public url: string;
  public token: string;
  selectedFiles?: FileList;
  currentFile?: File;
  progress = 0;
  message = '';

  constructor(
    private _userService: UserService,
    private _router: Router,
    private _route: ActivatedRoute,
    public _http: HttpClient
  ){
    this.user = new User(1,'','','','','','','','','ROLE_USER');
    this.is_edit=true;
    this.status='error';
    this.url=global.url;
    this.token = this._userService.getToken();
  }

  ngOnInit(): void {
    this.getUser();
  }

  getUser (){
    this._route.params.subscribe(
      params=>{
        let id = +params['id'];
        this._userService.getUser(id, this.token).subscribe(
          response=>{
            if (response.status =='success'){
            this.user = response.user;
          }else{
            this._router.navigate(['inicio']);
          }
          },error=>{
            console.log(error);
            this._router.navigate(['inicio']);
          });

      });
  }

  onSubmit(form: any){
    console.log(this.user);
    this._userService.updateUser(this.token, this.user).subscribe(
      response=>{
        this._router.navigate(['inicio']);
      },error=>{
        console.log(error);
      });

  }

  selectFile(event: any): void {
    this.selectedFiles = event.target.files;
    this.upload();
  }

  upload(): void {
    this.progress = 0;

    if (this.selectedFiles) {
      const file: File | null = this.selectedFiles.item(0);

      if (file) {
        this.currentFile = file;

        /*this._userService.upload(this.currentFile, this.token).subscribe({
          next: (event: any) => {
            if (event.type === HttpEventType.UploadProgress) {
              this.progress = Math.round((100 * event.loaded) / event.total);
            } else if (event instanceof HttpResponse) {
              this.message = event.body.message;
              this.user.image=event.body.image;
            }
          },
          error: (err: any) => {
            console.log(err);
            this.progress = 0;

            if (err.error && err.error.message) {
              this.message = err.error.message;
            } else {
              this.message = 'No se logro subir la imagen!';
            }

            this.currentFile = undefined;
          },
        });*/
      }

      this.selectedFiles = undefined;
    }
  }

}
