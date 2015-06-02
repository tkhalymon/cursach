#include <iostream>
#include <string.h>
using namespace std;

char S[5000]; // вхідний рядок

//вивести список символів
void show_symbols()
{
	int i = 0; // поточний символ рядку
	while (S[i] != '\0')
	{
		cout << i + 1 << ": " << S[i] << " ";
		i++;
	}
	cout << endl;
}

// знайти даожини мінімального можливого рядка
int find_min_string()
{
	int l = strlen(S); // довжина вхідного рядку
	int lenth = 0; // поточна довжина можливого рядку
	bool flag = true; // ознака знаходження найкоротшого можливого рядка
	cout << "To see next step press any key" << endl;
	for (int i = 1; i < l; i++) // починати з мінімальної можливої довжини
	{
		cout << "Cheking if string with lenth " << i << " is possible:" << endl;
		flag = true;
		getchar();
		cout << "--------------------------------------------------" << endl;
		// перевірка чи всі символи співпадуть, якщо поточний символ вважати почтком рядку
		for (int j = 0; j < l - i; j++)
		{
			if (S[j] != S[i + j]) // якщо відповідні символи не співпадають
			{
				cout << j + 1 << " symbol (" << S[j] << ") not equal to " << i + j + 1 << " symbol (" << S[i + j] << ")" << endl;
				cout << "Current string is not possible. Let's find other variants." << endl;
				flag = false; // поточний символ не може бути початком рядку
				break; // припинити перевірку
			}
			else
				cout << j + 1 << " symbol (" << S[j] << ") = " << i + j + 1 << " symbol (" << S[i + j] << ")" << endl;
		}
		if (flag) // якщо поточний символ може бути початком рядку
		{
			lenth = i; // його позицію записати як мінімальну можливу довжину рядка (так як ми рухаємося від найменшого, це гарантує знаходження саме мінімального рядка)
			break;  // зупинити пошуки
		}
	}
	return lenth;
}

int main()
{
	cout << "String S is written many times in a row. The part of this row are given to you." << endl;
	cout << "You should find lenth of minimal possible string S" << endl;
	cout << "Input repeated string: ";
	gets(S); // ввести рядок
	show_symbols();
	int answer = find_min_string(); // остаточна відповідь
	cout << "Current string is possible. So it's lenth is minimal possible case: ";
	cout << answer << endl;
	system("pause");
}