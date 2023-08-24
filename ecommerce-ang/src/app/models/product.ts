export class Product{
  constructor(
    public id: number,
    public name: string,
    public description: string,
    public price: number,
    public stock: number,
    public subcategory_id: string,
    public image: string,
    public image2: string,
    public image3: string,
    public user_id: string,
  ){}
}
