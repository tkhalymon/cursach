#include <iostream>

#define MIN(a,b) ((a) < (b) ? (a) : (b)) // знайти мін. значення
#define MAX(a,b) ((a) > (b) ? (a) : (b)) // знайти макс. значення

using namespace std;

int N, K; // розмір шахматної дошки та к-сть магарадж
int variants = 0, answer = 0; // поточна к-сть варіантів та остаточна відповідь
int** desk; // шахматна дошка

// поставити магараджу
void put_magaraja(int a, int b) // індекс рядка та стовпчика, куди ставимо магараджу
{
	// Магараджа б'є любу фігуру, що знаходиться в районі 3х3 клітки навколо магараджіб тому позначимо цю зону
	if (a > 1) // якщо над фігурою є 2 рядка, то помітити їх частину
	for (int j = MAX(0, b - 2); j < MIN(N, b + 3); j++) // помітити тільки 2 клітини зліва та справа від магараджі
	{
		desk[a - 2][j]++; 
		desk[a - 1][j]++;
	}
	else
	if (a > 0) // якщо один рядок, то помітити його частину
	for (int j = MAX(0, b - 2); j < MIN(N, b + 3); j++) // помітити тільки 2 клітини зліва та справа від магараджі
		desk[a - 1][j]++;
	if (a < N-2) // якщо під фігурою є 2 рядка, то помітити їх частину
	for (int j = MAX(0, b - 2); j < MIN(N, b + 3); j++) // помітити тільки 2 клітини зліва та справа від магараджі
	{
		desk[a + 2][j]++;
		desk[a + 1][j]++;
	}
	else
	if (a < N - 1) // якщо над фігурою є 1 рядок, то помітити його частину
	for (int j = MAX(0, b - 2); j < MIN(N, b + 3); j++) // помітити тільки 2 клітини зліва та справа від магараджі
		desk[a + 1][j]++;

	for (int i = 0; i < N; i++)
	{
		// помітити всі клітини по горизонталі та вертикалі
		desk[a][i]++;
		desk[i][b]++;
		// помітити всі клітини по діагоналям
		if (a - i >= 0)
		{
			if (b - i >= 0)
				desk[a - i][b - i]++;
			if (b + i < N)
				desk[a - i][b + i]++;
		}
		if (a + i < N)
		{
			if (b + i < N)
				desk[a + i][b + i]++;
			if (b - i >= 0)
				desk[a + i][b - i]++;
		}
	}
	desk[a][b] += 1000; // позначити місцеположення фігури
}

// прибрати магараджу (аналогічна попередній ф-ції, тільки навпаки, прибираєму помітки)
void get_of_magaraja(int a, int b)
{
	if (a > 1)
	for (int j = MAX(0, b - 2); j < MIN(N, b + 3); j++)
	{
		desk[a - 2][j]--;
		desk[a - 1][j]--;
	}
	else
	if (a > 0)
	for (int j = MAX(0, b - 2); j < MIN(N, b + 3); j++)
		desk[a - 1][j]--;
	if (a < N - 2)
	for (int j = MAX(0, b - 2); j < MIN(N, b + 3); j++)
	{
		desk[a + 2][j]--;
		desk[a + 1][j]--;
	}
	else
	if (a < N - 1)
	for (int j = MAX(0, b - 2); j < MIN(N, b + 3); j++)
		desk[a + 1][j]--;

	for (int i = 0; i < N; i++)
	{
		desk[a][i]--;
		desk[i][b]--;
		if (a - i >= 0)
		{
			if (b - i >= 0)
				desk[a - i][b - i]--;
			if (b + i < N)
				desk[a - i][b + i]--;
		}
		if (a + i < N)
		{
			if (b + i < N)
				desk[a + i][b + i]--;
			if (b - i >= 0)
				desk[a + i][b - i]--;
		}
	}
	desk[a][b] -= 1000;
}

// вивести поточний стан дошки
void output()
{
	for (int i = 0; i < N; i++)
	{
		for (int j = 0; j < N; j++)
		{
			if (desk[i][j] > 1000)
				cout << "M ";
			else
			{
				if (desk[i][j] == 0)
					cout << "0 ";
				else
					cout << "x ";
			}
		}
		cout << endl;
	}
	cout << endl;
}

// перебір варіантів
void Solution(int k) // номер фігури, яку ставимо на дошку
{
	if (k == K) // якщо залишилась остання фігура, то підрахувати всі можливі варіанти її розстановки
	{
		for (int i = 0; i < N; i++)
			for (int j = 0; j < N; j++)
				if (desk[i][j] == 0)
					variants++;
	}
	else // інакше по черзі розглянути всі можливі варіанти розстановки поточної фігури
	{
		for (int i = 0; i < N; i++)
			for (int j = 0; j < N; j++)
				if (desk[i][j] == 0)
				{
					put_magaraja(i, j);
					//getchar();
					output();
					Solution(k + 1);
					get_of_magaraja(i, j);
				}
	}
}

// почати підрахунок варіантів
void start()
{
	if (K == 1) // якщо магараджа лише одна
		answer = N*N; // то к-сть клітинок на дошці і буде відповіддю
	else
	{
		// для першої фігури достатньо розглянути частину випадків (трикутник чверті дошки), решта будуть аналогічними
		for (int i = 0; i < (N + 1) / 2; i++)
			for (int j = 0; j <= i; j++)
			{
				variants = 0; // обнулити к-сть варіантів для підрахунку варіантів у наступному випадку
				put_magaraja(i, j);
				getchar();
				cout << "Position of first figure:" << endl;
				cout << "---------------" << endl;
				output();
				if (K>2)
					cout << "All possible position of next figures: " << endl;
				Solution(2); // розглянути всі випадки для 2 фігури
				get_of_magaraja(i, j);
				if (i == N / 2) // якщо це середній (для непарних N) стовпчик
				{
					if (i == j) // якшо поточний елемент центральний
						answer += variants; // то такий випалок лише один
					else  //якщо, ні, то цей елемент знаходиться в середині рядку
						answer += 4 * variants; // тоді таких випадків 4
				}
				else // якщо стовпчик не останній
				{
					if (i == j) // якщо кутовий елеммент
						answer += 4 * variants; // то можливі 4 аналогічних випадка
					else // якщо ж елемент не кутовий, то це довільний(не середній) едемент рядка
						answer += 8 * variants; // тоді таких аналогічних випадків 8
				}
				cout << "all variants for this case: " << variants << endl;
			}
	}
}

int main()
{
	cout << "Made by Yevhen Serdiuk, IS-41, FICT" << endl;
	cout << "Magaraja it's chess figure wich can move like queen and horse." <<
		"\nYou need to find all variants how to put K(number of figures) this figures on desk NxN." << endl;
	cout << "To find out number of variants we need to put K-1 figures on desk and calculate all possible position of last figure." <<
		"\nDo it for every variant, but for the first figure we should't check all cases" << endl;
	cout <<"We need to check just element in triangle in one fourth of desk." << endl;
	cout << "Input N(size of the desk) and K(number of figures)" << endl;
	cin >> N >> K;
	cout << "To see next step press any key" << endl;
	desk = new int*[N];
	for (int i = 0; i < N; i++)
	{
		desk[i] = new int[N];
		for (int j = 0; j < N; j++)
			desk[i][j] = 0;
int
	start();
	int divide = 1; // к-сть перестановок
	// знаходимо к-сть перестановок магарадж (К!)
	for (int i = 1; i <= K; i++)
		divide *= i;
	cout << "-------------------------------------" << endl;
	cout << "Number of all possible variants: ";
	cout << answer/divide << endl; // ділимо к-сть варіантів на к-сть перестановок, адже не важливо порядок з яким ми ставимо магарадж
	for (int i = 0; i < N; i++)
		delete desk[i];
	system("pause");
}