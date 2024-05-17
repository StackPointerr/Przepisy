import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, map } from 'rxjs';
import { environment } from '../../../environments/environment';
import { Recipe } from '../models/recipe.model';

@Injectable({
    providedIn: 'root',
})
export class BookService {
    constructor(private http: HttpClient) {}

    public addToBook(id: string): Observable<any> {
        return this.http.post(environment.apiUrl + '/book/addToBook.php', { id }).pipe();
    }

    public removeFromBook(id: string): Observable<any> {
        return this.http.post(environment.apiUrl + '/book/removeFromBook.php', { id });
    }

    public getBook(): Observable<Array<Recipe>> {
        return this.http.post(environment.apiUrl + '/book/getAllBook.php', {}).pipe(
            map((response: any) => {
                if (response.success) {
                    return response.data;
                } else {
                    return [];
                }
            })
        );
    }
}
