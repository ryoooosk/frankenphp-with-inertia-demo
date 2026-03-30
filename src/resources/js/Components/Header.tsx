import { Link, router, usePage } from '@inertiajs/react';
import type { AuthProps } from '../types';

export default function Header() {
  const { auth } = usePage<AuthProps>().props;

  const handleLogout = () => {
    router.post('/logout');
  };

  return (
    <header className="h-13 w-full px-4 flex items-center justify-between border-b border-slate-800">
      <Link href="/" className="text-xl font-semibold tracking-wide text-slate-800">
        Inertia.js Demo
      </Link>

      <nav className="flex items-center gap-4">
        {auth.user ? (
          <>
            <span className="text-sm text-slate-600">{auth.user.name}</span>
            <button
              type="button"
              onClick={handleLogout}
              className="text-sm text-slate-600 hover:text-slate-800 transition-colors"
            >
              ログアウト
            </button>
          </>
        ) : (
          <>
            <Link
              href="/login"
              className="text-sm text-slate-600 hover:text-slate-800 transition-colors"
            >
              ログイン
            </Link>
            <Link
              href="/register"
              className="text-sm text-white bg-slate-800 px-3 py-1.5 rounded-md hover:bg-slate-700 transition-colors"
            >
              新規登録
            </Link>
          </>
        )}
      </nav>
    </header>
  );
}
