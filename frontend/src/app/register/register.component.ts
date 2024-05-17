import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { AuthenticationService } from '../core/services/authorization.service';

@Component({
    selector: 'app-register',
    standalone: true,
    imports: [CommonModule, FormsModule],
    templateUrl: './register.component.html',
    styleUrl: './register.component.scss',
})
export class RegisterComponent {
    username: string = '';
    password: string = '';

    response: any;

    constructor(private authenticationService: AuthenticationService) {}

    register() {
        this.authenticationService.register(this.username, this.password).subscribe({
            next: (response) => {
                this.response = response;
            },
        });
    }
}
