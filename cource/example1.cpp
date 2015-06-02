#include <iostream>

using namespace std;

int N, K, result = 0;  // к-сть лампочок, інверсій та кінцева відповідь
bool *lightbulbs; // масив лампочок
int *inversion; // масив інверсій

// вивід проміжного стану
void output()
{
	for (int i = 0; i < N; i++)
	{
		cout << i + 1 << ": "; // номер поточної лампочки
		if (lightbulbs[i]) // якщо лампока увімкнена
			cout << "on ";
		else
			cout << "off ";
	}
	cout << endl;
}

// зробити інверсії лампочок
void inverse()
{
	for (int i = 0; i < K; i++)
	{
		cout << i + 1 << " inversion: " << endl;
		int current_num = inversion[i]; // поточна лампочка для інверсії
		while (current_num <= N) // поки не всі можливі лампочки інверсовані
		{
			if (lightbulbs[current_num-1])  // якщо лампочка увімкнена
				lightbulbs[current_num-1] = false;  // то вимкнути
			else lightbulbs[current_num-1] = true;  // інакше увімункти
				current_num += inversion[i]; // перейти до наступної лампочки
		}
		output();  // вивести проміжний стан
	}
	for (int i = 0; i < N; i++)
		if (lightbulbs[i]) // якщо лампочка увімкнена
			result++; // збільшити результат на 1
}

int main()
{
	cout << "Lightbulb inversions." << endl;
	cout << "Enter number of lightbulbs and number of inversions: ";
	cin >> N >> K;
	lightbulbs = new bool[N];
	inversion = new int[K];
	cout << "Enter all inversions: " << endl;
	for (int i = 0; i < K; i++)
	{
		cout << i + 1 << ": ";
		cin >> inversion[i];
	}
	// позначити всі лампочки як вимкнені
	for (int i = 0; i < N; i++)
		lightbulbs[i] = false;
	inverse();
	cout << result << " lightbulbs will be on" << endl;
	delete lightbulbs;
	delete inversion;
	system("pause");
}