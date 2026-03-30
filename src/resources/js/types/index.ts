export type AuthUser = {
  id: number;
  name: string;
  email: string;
};

export type AuthProps = {
  auth: {
    user: AuthUser | null;
  };
};
