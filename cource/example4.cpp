#include <iostream>
#include <iomanip>
#include <list> // бібліотека списків
#define MIN(a,b) ((a) < (b) ? (a) : (b)) // знайти мін. значення

using namespace std;

int N, M, W, B, G; // NxM розміри матриці, W - ціна перефарбування в білий колір B - чорний ,G - сірий
int answer = 0; // вартість мінімального перефарбування
int** colors; // матриця кольорів
int** capacity; // пропускна здатність
int** flow; // потік

// перетворити матрицю кольорів в мережу
void make_net()
{
	int n = M*N + 2; // к-сть вершин в мережі (також додоємо джерело та стік)
	capacity = new int*[n]; 
	// створення матриці пропускних спроможностей
	for (int i = 0; i < n; i++)
	{
		capacity[i] = new int[n];
		for (int j = 0; j < n; j++)
			capacity[i][j] = 0;
	}
	//обхід вхідної матриці кольорів та заповнення матриці пропускних спроможностей
	for (int i = 0; i < N; i++)
		for (int j = 0; j < M; j++)
		{
			if (colors[i][j] == 1)   // джерело приєднаємо до всіх білих вершин
				capacity[0][i*M + j + 2] = B;  // з вагою ребра В
			else					 // а стік - до чорних
				capacity[i*M + j + 2][1] = W;  // з вагою ребра W
			// всі сусідні вершини, не зважаючи на колір з'єднаємо ребром вагою G
			if (i > 0)
				capacity[i*M + j + 2][(i - 1)*M + j + 2] = G;
			if (i < N - 1)
				capacity[i*M + j + 2][(i + 1)*M + j + 2] = G;
			if (j > 0)
				capacity[i*M + j + 2][i*M + j + 1] = G;
			if (j < M - 1)
				capacity[i*M + j + 2][i*M + j + 3] = G;
		}
	cout << "Capacity matrix of created net:" << endl;
	cout << "    ";
	for (int i = 0; i < n; i++)
		cout << setw(3) << i;
	cout << "\n--------------------------" << endl;
	for (int i = 0; i < n; i++)
	{
		cout << setw(3) << i << "|";
		for (int j = 0; j < n; j++)
			cout << setw(3) << capacity[i][j];
		cout << endl;
	}
}

// вивід поточного стану мережі
void output()
{
	int n = M*N + 2;
	cout << "  Edges     Flow" << endl;
	for (int i = 0; i < n; i++)
	{
		for (int j = 0; j < n; j++)
		{
			if (capacity[i][j] != 0)
				cout << setw(3) << i << " ->" << setw(3) << j << setw(5) << flow[i][j] << endl;
		}
	}
	cout << "-------------------------" << endl;
}

//знаходження максимального потоку
void max_flow()
{
	// алгоритм Форда-Фалкерсона
	// 0 - джерело; 1 - стік
	int n = M*N + 2; // к-сть вершин в мережі
	flow = new int*[n];
	// створення матриці потоку
	for (int i = 0; i < n; i++)
	{
		flow[i] = new int[n];
		for (int j = 0; j < n; j++)
			flow[i][j] = 0;
	}
	bool flag = true; // ознака збільшення потоку
	while (flag) // поки потік збільшується
	{
		flag = false; // потік ще не збільшився
		list<int> M; // множина вершин, які можуть збільшити потік
		int* reserve = new int[n]; // масив резервів вершин
		int* path = new int[n]; // попередник вершини, для встановлення шляху
		for (int i = 0; i < n; i++)
		{
			reserve[i] = 0;
			path[i] = 0;
		}
		reserve[0] = 999999; // резерв джерела - нескінченність
		M.push_back(0);
		while (!M.empty())  // якщо є вершини, які можуть збільшити потік
		{
			bool full_way = false; // ознака знаходження шляху від джерела до стоку
			int v = M.front(); // взяти довільну вершину з М
			M.pop_front(); // та видалити цю вершину з М
			for (int i = 0; i < n; i++)
			{
				// якщо з v в i(поточна вершина) існує ребро, пропускна здатність якого більша за поточний потік через це ребро та i ще не відвідано
				if (capacity[v][i] != 0 && reserve[i] == 0 && flow[v][i] < capacity[v][i])
				{
					reserve[i] = MIN(reserve[v], (capacity[v][i] - flow[v][i])); // встановити резерв і як мінімум з резерву v та різницею пропускної спроможності та потоку в поточному ребрі
					path[i] = v; // v - попередник i
					if (i != 1) // якщо і - не стік
						M.push_back(i);
					else // інакше пройдено шлях від джерела до стоку
						full_way = true;
				}
				else
					// якщо з і(поточна вершина) в v існує ребро, через яке потік не 0 та i ще не відвідано
				if (capacity[i][v] != 0 && reserve[i] == 0 && flow[i][v] > 0)
				{
					reserve[i] = MIN(reserve[v], flow[i][v]); // встановити резерв і як мінімум з резерву v та потоку в поточному ребрі
					path[i] = -v; // v - попередник i, через зворотнє ребро
					M.push_back(i);
				}
			}
			if (full_way) // якщо пройдено шлях від джерела до стоку
			{
				getchar();
				cout << "Way wich can increase flow is found: ";
				// відновити пройдений шлях
				for (int i = 1; i > 0; i = abs(path[i]))
				{
					cout << i << " <- ";
					if (path[i] >= 0)
						flow[path[i]][i] += reserve[1]; // ребру з правильною орієнтацією збільшити потік на резерв стоку
					else
						flow[i][abs(path[i])] -= reserve[1]; // ребру з протилежною орієнтацією зиеншити потік на резерв стоку
				}
				cout << "0" << endl;
				cout << "Current flow:" << endl;
				output();
				delete path;
				delete reserve;
				M.clear();
				flag = true; // потік збільшився
				break;
			}
		}
	}
	// порахувати максимальний потік як потік через розріз S/T, де S містить лиже джерело
	for (int i = 0; i < n; i++)
		answer += flow[0][i];
	for (int i = 0; i < n; i++)
	{
		delete flow[i];
		delete capacity[i];
	}
}

int main()
{
	cout << "Input matrix size (NxM): ";
	cin >> N >> M;
	cout << "Input cost of recoloring to White, Black and Gray: ";
	cin >> W >> B >> G;
	colors = new int*[N];
	cout << "Input colors. 1 - White, 2 - Black" << endl;
	// ввести матрицю кольорів
	for (int i = 0; i < N; i++)
	{
		colors[i] = new int[M];
		for (int j = 0; j < M; j++)
		{
			cout << "Color of element " << i + 1 << " " << j + 1 << ": ";
			cin >> colors[i][j];
		}
	}
	cout << "Entered color matrix:" << endl;
	cout << "   ";
	for (int i = 0; i < M; i++)
		cout << setw(2) << i+1;
	cout << "\n----------------" << endl;
	for (int i = 0; i < N; i++)
	{
		cout << setw(2) << i+1 << "|";
		for (int j = 0; j < M; j++)
			cout << setw(2) << colors[i][j];
		cout << endl;
	}
	cout << "Lets convert this matrix to the net" << endl;
	cout << "Add source and connect it to all white vertexes and sink - to black" << endl;
	cout << "Source - is vertex 0, sink - 1. Other vertexes you can define by formula:" << endl;
	cout << "[i*M + j + 2] , where i - number of row, j - column M - lenth of row" << endl;
	make_net();
	cout << "Now we need to find maximum flow - it will be an answer" << endl;
	cout << "To see next step press any key" << endl;
	max_flow();
	cout << "Way wich can increase flow is not found" << endl;
	cout << "________________________________________" << endl;
	cout << "So, minimum prise you can pay is: ";
	cout << answer << endl;
	system("pause");
}