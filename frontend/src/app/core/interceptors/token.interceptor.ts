import { HttpInterceptorFn, HttpResponse } from '@angular/common/http';
import { AuthenticationService } from '../services/authorization.service';
import { inject } from '@angular/core';
import { tap } from 'rxjs';

export const tokenInterceptor: HttpInterceptorFn = (req, next) => {
    const authenticationService = inject(AuthenticationService);

    if (authenticationService.isLoggedIn()) {
        req = req.clone({
            body: {
                ...(<object>req.body),
                token: authenticationService.getToken() ?? '',
            },
        });
    }

    return next(req).pipe(
        tap({
            next: (response) => {
                if (response instanceof HttpResponse) {
                    if ((<any>response.body)?.errors?.token) authenticationService.logout();
                }
            },
        })
    );
};
