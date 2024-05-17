import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';
import { environment } from '../../../environments/environment';

@Injectable({
    providedIn: 'root',
})
export class AuthenticationService {
    constructor(private http: HttpClient, private router: Router) {}

    public login(username: string, password: string): Observable<any> {
        return this.http
            .post(environment.apiUrl + '/auth/zaloguj.php', {
                username: username,
                password: password,
            })
            .pipe(
                map((response: any) => {
                    if (response.success) {
                        localStorage.setItem('token', response.token);
                        localStorage.setItem('id', response.user_id);
                        localStorage.setItem('username', username);
                        this.router.navigate(['/']);
                    }

                    return response;
                })
            );
    }

    public register(username: string, password: string): Observable<any> {
        return this.http
            .post(environment.apiUrl + '/auth/zarejestruj.php', {
                username: username,
                password: password,
            })
            .pipe(
                map((response: any) => {
                    if (response.success) {
                        localStorage.setItem('token', response.token);
                        localStorage.setItem('username', username);
                        localStorage.setItem('id', response.user_id);
                        this.router.navigate(['/']);
                    }

                    return response;
                })
            );
    }

    public logout(): void {
        localStorage.removeItem('token');
        this.router.navigate(['/login']);
    }

    public isLoggedIn(): boolean {
        let token = localStorage.getItem('token');
        return token != null && token.length > 0;
    }

    public getToken(): string | null {
        return this.isLoggedIn() ? localStorage.getItem('token') : null;
    }

    public getUserId(): string | null {
        return localStorage.getItem('id');
    }

    public getUsername(): string | null {
        return localStorage.getItem('username');
    }
}
