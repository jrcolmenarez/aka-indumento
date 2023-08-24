export class User{
  constructor(
    public id: number,
    public name: string,
    public surname: string,
    public address: string,
    public email: string,
    public password: string,
    public image: string,
    public phone: string,
    public gender: string,
    public role_user: string
  ){}
}
