type Truss = {
  id: number;
  name: string;
};

export default function Index() {
  const test: Truss = {
    id: 1,
    name: 'Test Truss',
  };
  return (
    <div className="min-h-dvh h-full flex flex-col">
      <header className="h-13 w-full px-2 flex items-center border-b border-slate-800">
        <h1 className="text-xl font-semibold tracking-wide text-slate-800">
          Inertia.js Demo
        </h1>
      </header>

      <div className="flex-1 px-2 my-3">
        <p>Hello World</p>
      </div>
    </div>
  );
}
