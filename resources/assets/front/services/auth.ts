import MainService from '@/services/main';
import AuthState, { type User } from '@/states/auth';

export default class AuthService {
  main: MainService;
  auth: AuthState;

  constructor(main: MainService) {
    this.main = main;
    this.auth = main.root.auth;
  }

  setUser(user: User) {
    this.auth.userId = user.id;
    this.auth.name = user.name;
    this.auth.email = user.email;
    this.auth.email_verified_at = user.email_verified_at;
    this.main.setRootState();
  }
}
