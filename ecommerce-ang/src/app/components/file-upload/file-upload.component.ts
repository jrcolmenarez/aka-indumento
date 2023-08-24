import { Component, OnInit } from '@angular/core';
import { HttpEventType, HttpResponse } from '@angular/common/http';
import { Observable } from 'rxjs';
import { FileUploadService } from 'src/app/services/file-upload.service';
import { UserService } from 'src/app/services/user.services';

@Component({
  selector: 'app-file-upload',
  templateUrl: './file-upload.component.html',
  styleUrls: ['./file-upload.component.css'],
  providers: [UserService]
})
export class FileUploadComponent implements OnInit {
  selectedFiles?: FileList;
  currentFile?: File;
  progress = 0;
  message = '';

 // fileInfos?: Observable<any>;

  constructor(private uploadService: FileUploadService,
              private _userservice: UserService
    ) {}

  ngOnInit(): void {
   // this.fileInfos = this.uploadService.getFiles();
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

        this._userservice.upload(this.currentFile).subscribe({
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
