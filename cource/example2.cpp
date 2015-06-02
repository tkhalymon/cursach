#include <iostream>
#include <fstream>

using namespace std;

struct edge
{
	int start; // початок ребра
	int end; // кінець ребра
	int veight; // вага ребра
	int number; // номер ребра
};

int n,m,k; // к-сть міст, доріг всього та доріг для покупки
edge * Edges; // масив ребер
int* vertexes; // масив з к-стю інцидентних ребер до кожної вершини

int comp(const void *i, const void *j) // елементи, що порівнюються
{
	edge* a = (edge*)i;
	edge* b = (edge*)j;
	return (a->veight - b->veight);  // порівнювати за вагою ребра
}

// вивід проміжних результатів
void output()
{
	cout << "--------------------------" << endl;
	for (int i = 0; i < n; i++)
	{
		cout << i + 1 << ": ";
		if (vertexes[i] == 0)
			cout << "not connected";
		else
		{
			for (int j = 0; j < m; j++)
			{
				if (Edges[j].number != 0 && (Edges[j].end == (i + 1) || Edges[j].start == (i + 1)))
				{
					if (Edges[j].end == (i + 1))
						cout << Edges[j].start << " (" << Edges[j].veight <<") ";
					else
						cout << Edges[j].end << " (" << Edges[j].veight << ") ";
				}
			}
		}
		cout << endl;
	}
	cout << "--------------------------" << endl;
}

// прочитати файл
void read()
{
	ifstream input("input.txt");
	input >> n >> m >> k;
	cout << "Yoy need to buy " << k << " roads" << endl;
	Edges = new edge[m];
	vertexes = new int[n];
	// позначити степені всіх вершин 0 для подальшого їх обрахунку
	for (int i = 0; i < n; i++)
		vertexes[i] = 0;
	cout << "Roads and their price:" << endl;
	for (int i = 0; i < m; i++)
	{
		cout << i + 1 << ": ";
		input >> Edges[i].start >> Edges[i].end >> Edges[i].veight;
		cout << Edges[i].start << " " << Edges[i].end << " " << Edges[i].veight << endl;
		Edges[i].number = i + 1; // зберегти початковий номер ребра
		// збільшити к-сть інцедентних ребер на 1 для початкової та кінцевої вершини
		vertexes[Edges[i].start - 1] ++;
		vertexes[Edges[i].end - 1] ++;
	}
	qsort(Edges, m, sizeof(edge), comp);
	input.close();
}

void input()
{
	cout << "Input number of towns, all roads and amount of roads to buy: ";
	cin >> n >> m >> k;
	Edges = new edge[m];
	vertexes = new int[n];
	// позначити степені всіх вершин 0 для подальшого їх обрахунку
	for (int i = 0; i < n; i++)
		vertexes[i] = 0;
	cout << "Input roads and their price:" << endl;
	for (int i = 0; i < m; i++)
	{
		cout << i + 1 << ": ";
		cin >> Edges[i].start >> Edges[i].end >> Edges[i].veight;
		Edges[i].number = i + 1; // зберегти початковий номер ребра
		// збільшити к-сть інцедентних ребер на 1 для початкової та кінцевої вершини
		vertexes[Edges[i].start - 1] ++;
		vertexes[Edges[i].end - 1] ++;
	}
	qsort(Edges, m, sizeof(edge), comp); // сортування ребер за їх вагою
}

void Solution()
{
	//Заданий граф - дерево, тому для знаходження мінімального варіанту,
	//відкидатимо термінальні вершини, до яких відстань найбільша
	for (int i = 0; i < (m-k); i++)
	{
		getchar();
		int j = m-1; // починати з ребра найбільшої ваги
		while (j >= 0)
		{
			// якщо ребро ще не відкинуте і одній з вершин інцидентне тільки це ребро
			if (Edges[j].number!= 0 && (vertexes[Edges[j].start - 1] == 1 || vertexes[Edges[j].end - 1] == 1))
			{
				cout << "We shouldn't buy road from " << Edges[j].start << " to " << Edges[j].end << "(delete this road from graph)" << endl;
				// зменшити к-сть інцедентних ребер на 1 для початкової та кінцевої вершини ребра
				vertexes[Edges[j].start-1] --;
				vertexes[Edges[j].end-1] --;
				Edges[j].number = 0; // позначити поточне ребро як видалене
				break;
			}
			j--;
		}
		output();
	}
	delete vertexes;
}

int main()
{
	cout << "Input graph is a tree. So to find out wich road we should buy" << endl;
	cout << "we need to delete roads wich conect terminal vertex \n(because we need to get a tree too) and are most expensive." << endl;
	cout << "We will do it while left only entered amount of roads wich we can buy" << endl;
	cout << "Read graph from example from file?[y/n]" << endl;
	if (getchar() == 'n') // якщо користувач вирішив ввести граф вручну
		input();
	else
		read();
	cout << "Input tree:" << endl;
	cout << "List of vertexes. In scobes you can see price of this road" << endl;
	output();
	cout << "To go to the next step just press any key" << endl;
	Solution();
	// вивести ребра, що залишилися
	cout << "So, you need to buy this roads:" << endl;
	for (int i = 0; i < m; i++)
	{
		if (Edges[i].number != 0)
			cout << Edges[i].number << ": " << Edges[i].start << " " << Edges[i].end << endl;
	}
	delete Edges;
	system("pause");
}