import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';
import { AuthenticationService } from '../../services/authorization.service';

@Component({
    selector: 'app-menu',
    standalone: true,
    imports: [RouterLink],
    templateUrl: './menu.component.html',
    styleUrl: './menu.component.scss',
})
export class MenuComponent {
    constructor(public authenticationService: AuthenticationService) {}
}
