import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { AuthenticationService } from '../core/services/authorization.service';

@Component({
    selector: 'app-login',
    standalone: true,
    imports: [CommonModule, FormsModule],
    templateUrl: './login.component.html',
    styleUrl: './login.component.scss',
})
export class LoginComponent {
    username: string = '';
    password: string = '';

    response: any;

    constructor(private authenticationService: AuthenticationService) {}

    login() {
        this.authenticationService.login(this.username, this.password).subscribe({
            next: (response) => {
                this.response = response;
            },
        });
    }
}
