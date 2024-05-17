import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { AuthenticationService } from '../services/authorization.service';

export const authGuard: CanActivateFn = (route, state) => {
    if (!inject(AuthenticationService).isLoggedIn()) {
        inject(Router).navigateByUrl('/login');
        return false;
    } else {
        return true;
    }
};
