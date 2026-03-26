import Header from '../Components/Header';

type Props = {
  children: React.ReactNode;
};

export default function AppLayout({ children }: Props) {
  return (
    <div className="min-h-dvh h-full flex flex-col">
      <Header />
      <div className="flex-1 flex flex-col">{children}</div>
    </div>
  );
}
