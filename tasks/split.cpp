#include <iostream>
#include <fstream>
#include <string.h>

using namespace std;

int main(int argc, char const *argv[])
{
	// 1 arg - input file name
	// 2 arg - output file base name
	// 3 arg - output file destination folder
	ifstream input;
	input.open(argv[1]);
	ofstream output;
	char basename[20] = "\0";
	char name[30] = "\0";
	if (strcmp(argv[3], "./") != 0)
	{
		strcpy(basename, argv[3]);
	}
	strcat(basename, argv[2]);
	strcpy(name, basename);
	char num[10];
	sprintf(num, "%d", 1);
	strcat(name, num);
	output.open(name);
	int n = 1;
	while (!input.eof())
	{
		char str[256];
		input.getline(str, 255);
		if (strncmp(str, "||", 2) == 0)
		{
			output.close();
			n++;
			strcpy(name, basename);
			sprintf(num, "%d", n);
			strcat(name, num);
			output.open(name);
		}
		else
		{
			if (strlen(str) != 0)
			{
				output << str << '\n';
			}
		}
	}
	cout << n;
	input.close();
	return 0;
}