#include <iostream>
using namespace std;
int main()
{
	char a[100];
	do
	{
		cin >> a;
		cout << a << a << "\n";
	}
	while (a[0] != '0');
	return 0;
}