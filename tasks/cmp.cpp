#include <iostream>
#include <fstream>
using namespace std;
int main()
{
	// user file
	char ufname[20];
	// solution file
	char sfname[20];
	cin >> ufname >> sfname;
	ifstream userfile;
	userfile.open(ufname);
	ifstream solfile;
	solfile.open(sfname);
	while (!userfile.eof() && !solfile.eof())
	{
		int u, s;
		userfile >> u;
		solfile >> s;
		if (u != s)
		{
			cout << "Incorrect answer";
			break;
		}
	}
	return 0;
}