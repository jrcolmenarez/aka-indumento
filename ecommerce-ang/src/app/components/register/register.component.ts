import { HttpClient, HttpHeaders, HttpEventType, HttpResponse } from '@angular/common/http';
import { User } from './../../models/user';
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { global } from 'src/app/services/global';
import { UserService } from '../../services/user.services';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css'],
  providers: [UserService]
})
export class RegisterComponent implements OnInit{
  public user: User;
  public is_edit: boolean;
  public status: string;
  public url: string;
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
      this.is_edit=false;
      this.status='error';
      this.url=global.url;
    }

    ngOnInit(): void {

    }
    onSubmit(form: any){
      this._userService.register(this.user).subscribe(
        response => {
           this._router.navigate(['inicio']);
        }, error => {
          console.log(<any>error);
        }
        );

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

          this._userService.upload(this.currentFile).subscribe({
            next: (event: any) => {
              if (event.type === HttpEventType.UploadProgress) {
                this.progress = Math.round((100 * event.loaded) / event.total);
              } else if (event instanceof HttpResponse) {
                this.message = event.body.message;
                console.log(event.body.image);
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
          });
        }

        this.selectedFiles = undefined;
      }
    }

}
